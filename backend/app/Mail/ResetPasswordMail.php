<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $resetUrl;

    public function __construct($token, $resetUrl)
    {
        $this->token = $token;
        $this->resetUrl = $resetUrl;
    }

    public function build()
    {
        return $this->subject('Đặt lại mật khẩu tài khoản D2warehouse của bạn')
            ->html('
        <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;">
            <div style="max-width: 800px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <h1 style="color: #333333;">Đặt lại mật khẩu của bạn</h1>
                <p style="font-size: 16px; color: #666666;">Chúng tôi nhận thấy bạn đã yêu cầu đặt lại mật khẩu. Để tiếp tục, vui lòng nhấp vào liên kết dưới đây:</p>
                <p style="font-size: 14px; color: #999999; margin-top: 20px;"><strong>Lưu ý: </strong> Link chỉ có hiệu lực trong 1 giờ.</p>
                <a href="' . $this->resetUrl . '" style="display: inline-block; background-color: #007bff; color: #ffffff; padding: 12px 25px; text-decoration: none; font-size: 16px; border-radius: 5px; margin-top: 20px;">Đặt lại mật khẩu</a>
                <p style="font-size: 14px; color: #999999; margin-top: 20px;">Nếu bạn không yêu cầu thay đổi mật khẩu, vui lòng bỏ qua email này.</p>
            </div>
        </div>
    ');
    }
}
