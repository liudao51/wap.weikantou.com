<?php
/**
 * Created by PhpStorm.
 * User: liuzw
 * Date: 2016/12/21
 * Time: 15:03
 */

namespace App\Http\Controllers\Home;

use App\Libs\ErrorInfo;
use App\Libs\Toolkit;
use App\Libs\ViewError;
use Illuminate\Http\Request;

/**
 * 账户控制器类
 *
 * Class AccountController
 * @package App\Http\Controllers\Home
 */
class AccountController extends BController
{
    /**
     * 登录界面
     *
     * @return object $view
     */
    public function getLogin()
    {
        return $this->responseSuccView('account.login');
    }

    /**
     * 登录处理
     *
     * @param Request $request 请求参数
     * @return object $view
     */
    public function postDologin(Request $request)
    {
        $request_data = $this->requestHandle($request);

        $read_type = ($request_data->has('read_type') && Toolkit::is_integer($request_data->get('read_type'))) ? trim($request_data->get('read_type')) : null;
        if (!isset($read_type) || ($read_type = intval($read_type, 10)) < 1) {
            return $this->responseFailView(ViewError::VIEW_404_1, '[read_type]' . ErrorInfo::Errors(2001), 2001);
        }

        //$userService = new UserService();
        $user = null;

        /**
         *  TODO: $read_type(1用户id, 2邮箱, 3邮箱+密码)
         */
        /*switch ($read_type) {
            case 1: {
                $user_id = ($request_data->has('id') && Toolkit::is_integer($request_data->get('id'))) ? trim($request_data->get('id')) : null;
                if (!isset($user_id) || intval($user_id, 10) < 1) {
                    return $this->responseFail('[id]' . ErrorInfo::Errors(2001), 2001);
                }

                $user = $userService->getUserById($user_id);

                break;
            }
            case 2: {
                $email = ($request_data->has('email') && Toolkit::is_email($request_data->get('email'))) ? trim($request_data->get('email')) : null;
                if (!isset($email)) {
                    return $this->responseFail('[email]' . ErrorInfo::Errors(2001), 2001);
                }

                $user = $userService->getUserByEmail($email);

                break;
            }
            case 3: {
                $email = ($request_data->has('email') && Toolkit::is_email($request_data->get('email'))) ? trim($request_data->get('email')) : null;
                if (!isset($email)) {
                    return $this->responseFail('[email]' . ErrorInfo::Errors(2001), 2001);
                }

                $pwd = ($request_data->has('pwd') && Toolkit::is_string($request_data->get('pwd'))) ? trim($request_data->get('pwd')) : null;
                if (!isset($pwd) || strlen($pwd) < 6) {
                    return $this->responseFail('[pwd]' . ErrorInfo::Errors(2001), 2001);
                }

                $user = $userService->getUserByEmailPwd($email, $pwd);

                break;
            }
        }*/

        $data['user'] = $user;

        return $this->responseSuccView('account.dologin', $data);
    }
}