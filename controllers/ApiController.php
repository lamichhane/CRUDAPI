<?php

/**
 * @author Yogendra Lamichhane
 */

namespace app\controllers;

use app\models\TblExam;
use app\models\TblExamQuery;
use yii\db\Query;
use yii\base\Exception;

class ApiController extends \yii\rest\Controller {

    public function actionIndex() {
	return $this->render('index');
    }

    /*
     * Create New Records
     * HTTP METHOD :POST
     */

    public function actionCreate() {
	$request = \Yii::$app->request;
	if ($request->isPost) {
	    $exam = new TblExam;
	    $exam->attributes = $request->bodyParams;
	    foreach ($request->bodyParams as $var => $value) {
		if ($exam->hasAttribute($var)) {
		    if ($var != 'id') {
			$exam->$var = $value;
		    }
		} else {
		    $this->sendResponse(500, 'Mistmatch Parameter');
		}
	    }

	    try {
		if ($exam->save()) {
		    $this->sendResponse(200, 'Record Created');
		}
	    } catch (Exception $ex) {
		$this->sendResponse(500, 'Couldnot create a record');
	    }
	} else {
	    $this->sendResponse(405, 'Only POST Method is allowed');
	}
    }

    /*
     * List all the records
     * HTTP METHOD :GET
     */

    public function actionList() {
	$request = \Yii::$app->request;
	if ($request->isGET) {
	    $tblExam = New TblExam;
	    try {
		$examList=$tblExam->finds()->all();
	    } catch (\Exception $e) {
		$this->sendResponse(500, 'No Records');
	    }
	    if (is_null($examList)) {
		    $this->sendResponse(200, 'No Records');
		} else {
		    $rows = [];
		    $temp = [];
		    foreach ($examList as $row) {
			$temp['id'] = $row['id'];
			$temp['name'] = $row['name'];
			$temp['tart_time'] = $row['start_time'];
			$temp['end_time'] = $row['end_time'];
			array_push($rows, $temp);
			unset($temp);
		    }
		    $this->sendResponse(200, $rows);
		}
	} else {
	    $this->sendResponse(405, 'Only GET Method is allowed');
	}
    }

    /*
     * Update records
     * HTTP METHOD :PUT
     */

    public function actionUpdate() {
	$request = \Yii::$app->request;
	if ($request->isPUT) {
	    $updateId = isset($_GET['id']) ? $_GET['id'] : '';
	    if ($updateId == '') {
		$this->sendResponse(500, 'Updating Key Missing');
	    } else {
		$tblExam = New TblExam;
		$updatingList = TblExam::findOne(["id" => intval($updateId)]);
		if (is_null($updatingList)) {
		    $this->sendResponse(500, 'Record Not Found For Update');
		}
		$updatingList->name = $request->bodyParams['name'];
		$updatingList->start_time = $request->bodyParams['start_time'];
		$updatingList->end_time = $request->bodyParams['start_time'];
		if ($updatingList->update()) {
		    $this->sendResponse(200, 'Record Updated');
		} else {
		    $this->sendResponse(500, 'Couldnot Update a Record');
		}
	    }
	} else {
	    $this->sendResponse(405, 'Only PUT Method is allowed');
	}
    }

    /*
     * Delete records based on Id
     * HTTP METHOD :DELETE
     */

    public function actionDelete() {
	$request = \Yii::$app->request;
	if ($request->isDELETE) {
	    $deleteItem = isset($_GET['id']) ? $_GET['id'] : '';
	    if ($deleteItem == '') {
		$this->sendResponse(500, 'Delete Parameter Missing');
	    } else {
		$deletingItem = TblExam::findOne(["id" => intval($deleteItem)]);
		if (!empty($deletingItem)) {
		    if ($deletingItem->delete()) {
			$this->sendResponse(200, 'Record Deleted');
		    }
		} else {
		    $this->sendResponse(200, 'Deleting Records Missing');
		}
	    }
	} else {
	    $this->sendResponse(405, 'Only DELETE Method is allowed');
	}
    }

    /*
     * List Records based on Id
     * HTTP METHOD :GET
     */

    public function actionView() {
	$request = \Yii::$app->request;
	if ($request->isGET) {
	    $searchItem = isset($_GET['id']) ? $_GET['id'] : '';
	    if ($searchItem == '') {
		$this->sendResponse(500, 'Search Parameter Missing');
	    } else {
		$searchResult = TblExam::findBySql(" select * from tbl_exam where (id like '%" . $this->Quote($searchItem) . "%'
		or name like '%" . $this->Quote($searchItem) . "%')")->all();
		if (empty($searchResult)) {
		    $this->sendResponse(200, 'No Record Found');
		} else {
		    $ret_arr = [];
		    $temp = [];
		    foreach ($searchResult as $site) {
			$temp['id'] = $site['id'];
			$temp['name'] = $site['name'];
			$temp['tart_time'] = $site['start_time'];
			$temp['end_time'] = $site['end_time'];
			array_push($ret_arr, $temp);
			unset($temp);
		    }
		    $this->sendResponse(200, $ret_arr);
		}
	    }
	} else {
	    $this->sendResponse(405, 'Only GET Method is allowed');
	}
    }

    /**
     * Sends the API response 
     * @param int $status 
     * @param string $message 
     * @return void
     */
    private function sendResponse($status, $message) {
	$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusMessage($status);
	//set Header
	header($status_header);
	// set the content type
	header('Content-type: application/json');
	if ($status == 200) {
	    $response["success"] = true;
	    $response["message"]["success"]["text"] = $message;
	} else {
	    $response["error"] = true;
	    $response["message"]["error"]["text"] = $message;
	}
	echo json_encode($response);
	exit;
    }

    /**
     * Get corresponding http error message based on http code
     * @param mixed $status 
     * @return string
     */
    private function getStatusMessage($status) {
	$httpCodes = Array(
	    400 => 'Bad Request',
	    404 => 'File Not Found',
	    405 => 'Method Not Allowed',
	    500 => 'Internal Server Error'
	);
	return (isset($httpCodes[$status])) ? $httpCodes[$status] : '';
    }

    /*
     * Add a backslash in front of the character
     * @param string $str 
     * @return string
     */

    private function Quote($str) {
	return addcslashes(str_replace("'", "''", $str), "\000\n\r\\\032");
    }

}
