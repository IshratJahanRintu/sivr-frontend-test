import React, {useEffect, useState} from "react";
import {useNavigate} from 'react-router-dom';
import axios from 'axios';
import Cookies from 'universal-cookie';
export default function LoginWithApiUrl() {
    const [msg, setMsg] = useState('Redirecting ...');
    const navigate = useNavigate();
    const cookies = new Cookies();
    useEffect(() => {
        handleLogin();
    },[])

    const handleLogin=async ()=> {

        let search = window.location.search;
        let params = new URLSearchParams(search);
        let authenticateInfo = params.toString();
        authenticateInfo = authenticateInfo.substring(0, authenticateInfo.length - 1);
        let authCode = authenticateInfo.substring(0, 12);


        console.log(authCode)

        const cli = "0" + authenticateInfo.substring(12, authenticateInfo.length);
        if (isPhoneNumberValid(cli)) {
            const data = {
                cli: cli,
                authCode: authCode
            }
            try {

                const response = await axios.post('http://127.0.0.1:8000/api/login/auth', data);
                const responseData = response.data;
                setMsg(responseData.message);

                cookies.set('token', responseData.data.token, {path: '/'});
                cookies.set('key', responseData.data.key, {path: '/'});
                navigate('/homePage');
            } catch (error) {
                if (error.response ) {
                    setMsg(error.response.data.message);
                }
            }
        } else {

            setMsg("Invalid Number");

        }

    }

    function isPhoneNumberValid(phoneNumber) {

        if (!/^\d+$/.test(phoneNumber)) {

            return false;
        }
        const ValidNumberRegex = /^(?:\+?88|0088)?01\d{9}$/;

        return ValidNumberRegex.test(phoneNumber);

    }

    return (
        <h1> {msg}</h1>
    )
}
