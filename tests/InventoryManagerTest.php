<?php

/**
 * Description of InventoryManagerTest
 *
 * @author Josiah Gerald
 */

use PHPUnit\Framework\TestCase;

require_once 'InventoryManager.php';

class InventoryManagerTest extends TestCase {
    
    public function testInventoryDataCanBeSet()
    {
        $data = ['product_name' => "Rice", 'stock_qty' => 200, 'item_price' => 200];
        $this->assertNotEmpty($data);
        $inventory = new InventoryManager();
        $this->assertTrue($inventory->setInventoryData($data));
        
        return $inventory;
    }
    
    /**
     * @depends testInventoryDataCanBeSet
     */
    public function testCanGetInventoryDataAfterSet(InventoryManager $inventoryObj)
    {
        $this->assertInstanceOf('InventoryManager', $inventoryObj);
        $inventoryData = $inventoryObj->getInventoryData();
        $this->assertIsArray($inventoryData);
        $this->assertNotEmpty($inventoryData);
        $this->assertArrayHasKey('product_name', $inventoryData);
    }
    
    /**
     * @small
     */
    public function testGetInventoryDataIsArray()
    {
        $inventory = new InventoryManager();
        $inventoryData = $inventory->getInventoryData();
        $this->assertIsArray($inventoryData);
    }
    
    /**
     * @depends testInventoryDataCanBeSet
     */
    public function testGetInventoryDataHasValidData(InventoryManager $inventoryObj)
    {
        $inventoryData = $inventoryObj->getInventoryData();
        $this->assertIsArray($inventoryData);
        $this->assertCount(3,$inventoryData);
        $this->assertArrayHasKey('product_name', $inventoryData);
        $this->assertArrayHasKey('stock_qty', $inventoryData);
        $this->assertArrayHasKey('item_price', $inventoryData);
    }
    
    /*
     * Test getInventory
     */
    public function testGetInventory()
    {
        $inventory = new InventoryManager();
        $this->assertIsReadable($inventory->getDataStoreFile());
    }
}
