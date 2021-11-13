import http from '../http-common';

class OTPService
{
    requestOtp(data)
    {
        return http.post("otp", data);
    }

    verify(data)
    {
        return http.post("verify", data);
    }
}
export default new OTPService();