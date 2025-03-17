<?php
namespace {PLUGIN_NAME}\Services;

defined('ROOT') or die("Direct script access denied");

interface {CLASS_NAME}
{
    public function getList();
    public function getFirst($id);
    public function addCreate($data,$lastinsertId);
    public function update($id,$data);
    public function delete($id);
}