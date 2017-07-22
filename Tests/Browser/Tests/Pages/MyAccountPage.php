<?php

require_once 'PageBase.php';

class MyAccountPage extends PageBase {
    const MY_ACCOUNT_URL = self::BASE_URL . 'my-account';

    public function testMyAccount_Page_Allows_New_User_To_Register() {
        $this->webDriver->get(self::MY_ACCOUNT_URL);

        $this->webDriver->findElement(WebDriverBy::id('reg_email'))->sendKeys('wxy@xz.com');
        $this->webDriver->findElement(WebDriverBy::name('register'))->click();
        
        try {
            $this->webDriver->wait(12, 250)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('woocommerce-MyAccount-navigation'))
            );
            return true;
        }
        catch (Exception $e) {
            return false;
        }
        finally {
        }
    }

    public function testMyAccount_Page_Allows_Registered_User_To_Login() {
        $this->webDriver->get(self::MY_ACCOUNT_URL);

        $this->webDriver->findElement(WebDriverBy::id('username'))->sendKeys('selenium');
        $this->webDriver->findElement(WebDriverBy::id('password'))->sendKeys('selenium');
        $this->webDriver->findElement(WebDriverBy::name('login'))->click();
        
        $this->webDriver->wait(2, 250)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::partialLinkText('Dashboard'))
        );

        return true;
     }
}
