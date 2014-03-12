<?php 
class MyController {
    public function getAction($request) {
    	echo "MyController.getAction();";
		return true;
	}
    public function postAction($request) {
    	echo "MyController.postAction();";
		return true;
	}
}

?>