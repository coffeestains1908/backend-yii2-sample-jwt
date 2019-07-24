<?php

namespace app\modules\api\controllers;

use Yii;

use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;

use yii\rest\Controller;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => [
                'login',
            ],
        ];

        return $behaviors;
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionLogin()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
            $jwt = Yii::$app->jwt;
            $token = $jwt->getBuilder()
                ->setIssuer('http://apps.harwood.com')// Configures the issuer (iss claim)
                ->setAudience('harwood.mobile.com')// Configures the audience (aud claim)
                ->setId('4f1g23a12aa', true) // change to emp_id later
                ->setIssuedAt(time())
                ->setExpiration(time() + 3600)
                ->set('uid', 100) // not sure what this does
                ->sign($signer, $jwt->key)
                ->getToken();
    
            return $this->asJson([
                'token' => (string)$token,
            ]);
        }

        throw new \yii\base\ViewNotFoundException;
    }

    public function actionData() {
        return $this->asJson([
            'success' => true,
        ]);
    }
}
