import React, {useEffect, useState} from "react";
import {API_URL, LOGIN_AUTH_API_URL} from "../config/Constants";
import {useNavigate} from 'react-router-dom';
import axios from 'axios';

export default function LoginWithApiUrl() {
    const [msg, setMsg] = useState('Redirecting ...');
    const navigate = useNavigate();
    useEffect(() => {
        handleLogin();
    })

    async function handleLogin() {

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
                console.log(response);
                console.log(cli);

                navigate('/homePage');
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    setMsg('Unauthorized');
                } else {
                    setMsg('Login failed');
                }
            }
        } else {
            console.log('invalid number');
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
