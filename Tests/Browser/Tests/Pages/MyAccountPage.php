<?php
setcookie('myvv', 'elmo@sesamestreet.com', time() + (86400 * 30), "/");

require_once 'PageBase.php';
require '../Repository/userRepository.php';

class MyAccountPage extends PageBase {
    const URL = self::BASE_URL . 'my-account';

    public function testMyAccount_Page_Allows_New_User_To_Register() {
        $userRepository = new UserRepository;
        $userRepository->deleteByEmail('f@z.com');

        $this->webDriver->get(self::URL);

        $this->webDriver->findElement(WebDriverBy::id('reg_email'))->sendKeys('f@z.com');
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
            $userRepository->deleteByEmail('f@z.com');
        }
    }

    public function testUserWithEmailCookieRecognized() {
        $userRepository = new UserRepository;

        $_COOKIE['myvv'] = 'elmo@sesamestreet.com';

        $email = $_COOKIE['myvv'];
        $user = $userRepository->findByEmail($email);

        $_COOKIE['myvv'] = 'elmo@sesamestreet.com';

        if ($user) return $user['userName']==='Elmo';
        return false;
    }

    public function testMyAccount_Page_Allows_Registered_User_To_Login() {
        $this->webDriver->get(self::URL);

        $this->webDriver->findElement(WebDriverBy::id('username'))->sendKeys('selenium');
        $this->webDriver->findElement(WebDriverBy::id('password'))->sendKeys('selenium');
        $this->webDriver->findElement(WebDriverBy::name('login'))->click();
        
        $this->webDriver->wait(2, 250)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::partialLinkText('Dashboard'))
        );

        return false;
     }
}
