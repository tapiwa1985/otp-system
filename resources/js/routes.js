import RequestOtp from "./components/RequestOtp";
import VerifyOtp from "./components/VerifyOtp";


export const routes = [
    {
        name: 'otp',
        path: '/otp',
        component: RequestOtp
    },
    {
        name: 'login',
        path: '/verify',
        component: VerifyOtp
    }
];