<?php

class MagicMike_Oprema_Model_Stanje
{

    public function opremaUpdateStock(){
        try{
            /** @var MagicMike_Oprema_Model_Import_Api $api */
            $api = Mage::getModel('magicmike_oprema/import_api');

            if ( !$productData = $api->call('update') ) {
                Mage::log(
                    time() . PHP_EOL . 'No data received from api oprema', Zend_Log::ERR,'opremaApi.log'
                );
                return;
            }

            $importData = [];
            foreach ($productData as $itemData){

            }

            /** @var $import AvS_FastSimpleImport_Model_Import */
            $import = Mage::getModel('fastsimpleimport/import');
            $import
                ->setPartialIndexing(true)
                ->setBehavior(Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE)
                ->setContinueAfterErrors(true)
                ->setIgnoreDuplicates(true)
                ->setErrorLimit(100);

            try {
                $import->processProductImport($importData);

                Mage::log("\n Finished stock update: " . time() . "\n");
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        catch (\Exception $e){
            Mage::log($e->getMessage());
        }
    }


}