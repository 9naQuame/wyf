<?php
class PgFileStore extends postgresql 
{
    private static $model = null;

    private static function getModel() 
    {
        if (PgFileStore::$model == null) 
        {
            PgFileStore::$model = Model::load("system.binary_objects");
        }
        return PgFileStore::$model;
    }

    public static function addFile($path) 
    {
        return PgFileStore::addData(file_get_contents($path));
    }

    public static function addData($data) 
    {
        $pdo = Db::get();
        $statement = $pdo->prepare("INSERT INTO system.binary_objects(data) VALUES(?)");
        $statement->bindParam(1, $data, PDO::PARAM_LOB);
        $statement->execute();
        $id = Db::query("SELECT LASTVAL()");
        return $id[0]['lastval'];
    }

    public static function getData($oid) 
    {
        $model = PgFileStore::getModel();
        $data = $model->getWithField("object_id", $oid);
        return stream_get_contents($data[0]['data']);
        
        //$data[0]["data"];
    }

    public static function getFile($oid) 
    {
        $model = PgFileStore::getModel();
        $data = $model->getWithField("object_id", $oid);
        return $data[0]['data'];
    }

    public static function getFilePath($oid, $postfix = "picture.jpg") 
    {
        $filePath = 'app/temp/' . $oid . "_$postfix";
        $model = PgFileStore::getModel();
        $data = $model->getWithField("object_id", $oid);
        $file = fopen($filePath, 'w');
        stream_copy_to_stream($data[0]['data'], $file);
        return $filePath;
    }

    public static function deleteFile($oid) 
    {
        $model = PgFileStore::getModel();
        $model->delete("object_id", $oid);
    }
}
