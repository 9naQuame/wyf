<?php

class MCDataImporterJob extends ajumamoro\Ajuma
{
    private $fileFields = array();
    private $headers;
    private $displayData;
    private $modelData;
    private $modelInstance;
    private $secondaryKey;
    private $tertiaryKey;
    
    public function run()
    {
        try{
            $status = array(
                'statuses' => $this->go(),
                'headers' => $this->headers,
                'message' => 'Succesfully Imported'
            );
        }
        catch(Exception $e)
        {
            $status = $e->getMessage();
        }
        return $status;
    }
    
    private function setModelData($data)
    {
        $hasValues = false;
        
        foreach($data as $i => $value)
        {
            if(trim($value) !== '') 
            {
                $hasValues = true;
            }
            
            $this->fields[$i]->setWithDisplayValue($value);
            $this->displayData[$this->fileFields[$i]->getName()] = $value;
            $this->modelData[$this->fileFields[$i]->getName()] = $this->fields[$i]->getValue();
        } 
        return $hasValues;
    }
    
    /**
     * Maps the fields on the file to those on the form.
     */
    private function setupFileFields()
    {
        foreach($this->fields as $field)
        {
            $index = array_search($field->getLabel(), $this->headers);
            if($index !== false)
            {
                $this->fileFields[] = $field;
            }
            else
            {
                throw new Exception("Invalid file format could not find the {$field->getLabel()} column");
            }
        }        
    }
    
    private function updateData()
    {
        $this->secondaryKey = $
        $tempData = reset(
            $this->modelInstance->getWithField(
                $this->secondaryKey,
                $this->modelData[$this->secondaryKey]
            )
        );
        
        if($tempData !== false) 
        {
            if($this->tertiaryKey != "")
            {
                $this->modelData[$this->primaryKey] = $tempData[0][$this->primaryKey];
                $this->modelData[$this->tertiaryKey] = $tempData[0][$this->tertiaryKey];
            }

            $validated = $this->modelInstance->setData(
                $this->modelData,
                $this->primaryKey,
                $this->tempData[0][$this->primaryKey]
            );
            
            if($validated===true) 
            {
                $this->modelInstance->update(
                    $this->primaryKey,
                    $tempData[0][$this->primaryKey]
                );
                return 'Updated';
            }
            else
            {
                return $validated;
            }
        }
        else
        {
            return $this->addData();
        }        
    }
    
    private function addData()
    {
        $validated = $this->modelInstance->setData($this->modelData);
        if($validated===true) 
        {
            $this->modelInstance->save();
            return 'Added';  
        }   
        else
        {
            return $validated;
        }
    }
    
    public function go()
    {
        $file = fopen($this->file, "r");
        $statuses = array();
        $this->headers = fgetcsv($file);
        $this->modelInstance = Model::load($this->model);
        
        $this->setupFileFields();
        
        $this->primaryKey = $this->modelInstance->getKeyField();
        $this->tertiaryKey = $this->modelInstance->getKeyField("tertiary");
        $this->secondaryKey = $this->modelInstance->getKeyField('secondary');
        $hasErrors = false;
        

        $this->modelInstance->datastore->beginTransaction();

        while(!feof($file))
        {
            $data = fgetcsv($file);
            $this->modelData = array();
            
            if(!is_array($data)) 
            { 
                continue; 
            }
            
            if(!$this->setModelData($data)) 
            {
                continue;
            }
            
            if($this->secondaryKey!=null && $$this->modelData[$this->secondaryKey] != '')
            {
                $validated = $this->updateData();
            }
            else
            {
                $validated = $this->addData();
            }

            if(isset($validated['errors']))
            {
                $hasErrors = true;
                $statuses[] = array(
                    'success' => false,
                    'data' => $this->displayData,
                    'errors' => $validated['errors']
                );
            }
            else
            {
                $statuses[] = array(
                    'success' => true,
                    'data' => $this->displayData
                );
            }
        }
        
        unlink($this->file);
        
        if(!$hasErrors) 
        {
            $this->modelInstance->datastore->endTransaction();
        }
        
        return $statuses;       
    }
}

