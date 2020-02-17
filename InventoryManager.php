<?php

/**
 * Description of InventoryManager
 *
 * @author Josiah Gerald
 */

class InventoryManager 
{
    
    protected $inventoryData = [];
    private $dataStoreFile = __DIR__."/inventory.json";
    public $error = [];

    public function __construct() 
    {
        
    }
    
    public function getDataStoreFile()
    {
        return $this->dataStoreFile;
    }


    public function setInventoryData(array $data) :bool
    {
        if(!empty($data)){
            $this->inventoryData = filter_var_array($data,FILTER_SANITIZE_STRING);
            return true;
        }
        return false;
    }
    
    public function getInventoryData():array 
    {
        return $this->inventoryData;
    }
    
    public function saveInventory(array $data) :bool
    {
        if(empty($data)){
            $this->error['error'] = "Inventory data is blank";
            return false;
        }
        //calculate total value number
        $data['total_value'] = $data['stock_qty'] * $data['item_price'];
        //get date of submission in timestamp
        $data['date_submitted'] = time();
        //create a file if not existing
        if(!is_readable($this->dataStoreFile)){
            if(file_exists($this->dataStoreFile)){
                chmod($this->dataStoreFile, 0775);
            }
            else{
                $fileHandler = fopen($this->dataStoreFile, "x");
                if(!$fileHandler){
                    $this->error['error'] = "Could not create file: ". $this->dataStoreFile;
                    return false;
                }
                fclose($fileHandler);
            }
        }
        //open datastore file and save data
        if(filesize($this->dataStoreFile) <= 0){
            $fileHandler = fopen($this->dataStoreFile, "w");
            if(!$fileHandler){
                $this->error['error'] = "Could not open file: ". $this->dataStoreFile;
                return false;
            }
            $dataStore = [];
            $dataStore[] = $data;
            fwrite($fileHandler, json_encode($dataStore,JSON_PRETTY_PRINT));
            fclose($fileHandler);
        }
        else{
//            $fileHandler = fopen($this->dataStoreFile, "r");
//            if(!$fileHandler){
//                $this->error['error'] = "Could not open file: ". $this->dataStoreFile;
//                return false;
//            }
//            $dataStoreJson = fread($fileHandler, filesize($this->dataStoreFile));
//            $dataStore = json_decode($dataStoreJson,JSON_OBJECT_AS_ARRAY);
//            array_push($dataStore, $data);
//            fwrite($fileHandler, json_encode($dataStore));
            $dataStoreJson = file_get_contents($this->dataStoreFile);
            $dataStore = json_decode($dataStoreJson,JSON_OBJECT_AS_ARRAY);
            array_push($dataStore, $data);
            file_put_contents($this->dataStoreFile, json_encode($dataStore,JSON_PRETTY_PRINT));
        }
        //fclose($fileHandler);
        return true;
    }
    
    public function getInventory()
    {
        if(!is_readable($this->dataStoreFile)){
            if(file_exists($this->dataStoreFile)){
                chmod($this->dataStoreFile, 0775);
            }
            else{
                return false;
            }
        }
        //open datastore file and read data
//        $fileHandler = fopen($this->dataStoreFile, "r");
//        if(!$fileHandler){
//            $this->error['error'] = "Could not read file: ". $this->dataStoreFile;
//            return false;
//        }
//        $dataStoreJson = fread($fileHandler, filesize($this->dataStoreFile));
//        fclose($fileHandler);
        $dataStoreJson = file_get_contents($this->dataStoreFile);
        return $dataStoreJson;
    }
}
if($_POST){
    $inventory = new InventoryManager();
    $inventory->setInventoryData($_POST);
    $formData = $inventory->getInventoryData();
    $result = $inventory->saveInventory($formData);
    if(!empty($inventory->error)){
        echo json_encode($inventory->error);
        return false;
    }
    $inventoryRecords = $inventory->getInventory();
    if(!empty($inventory->error)){
        echo json_encode($inventory->error);
        return false;
    }
    echo $inventoryRecords;
}
if($_GET && $_GET['get'] == 'inventory'){
    $inventory = new InventoryManager();
    $inventoryRecords = $inventory->getInventory();
    if(!empty($inventory->error)){
        echo json_encode($inventory->error);
        return false;
    }
    echo $inventoryRecords;
}