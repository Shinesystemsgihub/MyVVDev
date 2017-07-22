<?php

use Facebook\WebDriver;

class PageBase
{
    const BROWSER_NAME = 'chrome';
    const BROWSER_URL = 'localhost:4444/wd/hub';
    const BASE_URL = 'http://dev.myvacayvalet.com/';
    // const BASE_URL = 'http://localhost:3000/';
    
    const CAPABILITIES = array(
        \WebDriverCapabilityType::BROWSER_NAME => self::BROWSER_NAME,
        \WebDriverCapabilityType::ACCEPT_SSL_CERTS => true
    );

    public function StartTest() 
    {
        $this->webDriver = RemoteWebDriver::create(self::BROWSER_URL, self::CAPABILITIES, 2000);
    }

   public function EndTest()
   {
       $this->webDriver->quit();
   }
}
