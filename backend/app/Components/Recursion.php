<?php

namespace App\Components;

class Recursion
{

    private $data;
    private $htmlselect='';
    public function __construct($data)
    {
        $this->data = $data;
    }
    function cat_parent($id = 0, $text = '')
    {
        $space =str_repeat('&nbsp;', 5); 
        foreach ($this->data as $val) {
            if ($val['parent_id'] == $id) {
                $this->htmlselect .= "<option value=".$val['id'].">" . $text . $val['TenLoai'] . "</option>";
                $this->cat_parent($val['id'], $text .$space);
            }
        }
        return $this->htmlselect;
    }
    function catParentSelected($id = 0, $text = '',$id_selected=0)
    {
        foreach ($this->data as $val) {
            
                $selected = $id_selected==$val['id']?' selected':'';
                $this->htmlselect .= "<option".$selected." value=".$val['id'].">" . $text . $val['TenLoai'] . "</option>";

            
        }
        return $this->htmlselect;
    }
    function cat_parent_selected($id = 0, $text = '',$check_id=null)
    {
        $space =str_repeat('&nbsp;', 5); 
        foreach ($this->data as $val) {
            if ($val['parent_id'] == $id) {
                $selected = $check_id==$val['id']?' selected':'';
                $this->htmlselect .= "<option ".$selected." value=".$val['id'].">" . $text . $val['TenLoai'] . "</option>";
                $this-> cat_parent_selected($val['id'], $text .$space,$check_id);
            }
        }
        return $this->htmlselect;
    }
}
