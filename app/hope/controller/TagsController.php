<?php
class TagsController extends ModelController{
    
    public function add(){
        
        $text = trim($_POST['text']);
        $pid  = $_POST['pid'];        
        $arr = explode('#', $text);
        $tagsDB = new TagsModelDB();
        foreach($arr as $key=>$name){
            if($tagsDB->isExist($pid, $name)){
                $tagsDB->up($pid, $name);
            }else{
                $tagsDB->add($pid, $name);
            }
        }
        echo json_encode(array('code'=>0));
    }
}
