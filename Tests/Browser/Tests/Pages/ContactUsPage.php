<?php

require_once 'PageBase.php';

class ContactUsPage extends PageBase {
    const CONTACT_US_URL = self::BASE_URL.'contact-us';

    public function ContactUs_Page_Presents_ContactUs_Form_Notifies_Successful_Transmission() {
        $this->webDriver->get(self::CONTACT_US_URL);

        $this->webDriver->findElement(WebDriverBy::name('your-name'))->sendKeys('Bon Jovi');
        $this->webDriver->findElement(WebDriverBy::name('your-email'))->sendKeys('bj@agingstars.com');
        $this->webDriver->findElement(WebDriverBy::name('your-subject'))->sendKeys('Possum filets?');
        $this->webDriver->findElement(WebDriverBy::name('your-message'))->sendKeys("Need to double our order for possum filets for tomorrow's soiree");

        $this->webDriver->switchTo()->frame($this->webDriver->findElement(WebDriverBy::cssSelector('iframe[title="recaptcha widget"]')));
        $clickable = $this->webDriver->findElement(WebDriverBy::id('recaptcha-anchor'))->click();
        $this->webDriver->wait(10, 250)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.recaptcha-checkbox-checked'))
        );
        $this->webDriver->switchTo()->defaultContent();
        $this->webDriver->findElement(WebDriverBy::cssSelector('.wpcf7-form-control.wpcf7-submit'))->click();
        
        $this->webDriver->wait(2, 250)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('wpcf7-response-output'))
        );

        $response = $this->webDriver->findElement(WebDriverBy::className('wpcf7-response-output'))->getText();
        $expected = 'Thank you for your message. It has been sent.';
        return $response === $expected;
     }

    public function Invalid_ContactUs_Page_Presents_Error_Message() {
        $this->webDriver->get(self::CONTACT_US_URL);

        $this->webDriver->findElement(WebDriverBy::name('your-name'))->sendKeys('Bon Jovi');
        $this->webDriver->findElement(WebDriverBy::name('your-email'))->sendKeys('bj@agingstars.com');
        $this->webDriver->findElement(WebDriverBy::name('your-subject'))->sendKeys('Possum filets?');
        $this->webDriver->findElement(WebDriverBy::className('wpcf7-submit'))->click();
        
        $this->webDriver->wait(2, 250)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('wpcf7-response-output'))
        );

        $response = $this->webDriver->findElement(WebDriverBy::className('wpcf7-response-output'))->getText();
        $expected = 'One or more fields have an error. Please check and try again.';
        return $response === $expected;
     }
}
