import React, {useEffect, useState} from "react";
import {API_URL, LOGIN_AUTH_API_URL} from "../config/Constants";
import axios from 'axios';

export default function LoginWithApiUrl(){
    const [msg,setMsg]=useState('Loading...');
    useEffect(()=>{
        handleLogin();
    })

    async function handleLogin() {
        let search = window.location.search;
        let params = new URLSearchParams(search);
        let authenticateInfo = params.toString();
        authenticateInfo = authenticateInfo.substring(0, authenticateInfo.length - 1);
        let authCode = authenticateInfo.substring(0, 12);
        let alphaNumExp = /^[A-Za-z0-9]+$/;

        if ( alphaNumExp.test(authCode)) {
            console.log(authCode)

            const cli = 0+authenticateInfo.substring(12, authenticateInfo.length);
            if (isPhoneNumberValid(cli)) {
                const data = {
                    cli: cli,
                    authCode: authCode
                }
                try {

                    const response = await axios.post('http://127.0.0.1:8000/api/login/auth', data);
                    setMsg(response.data.message);
                    console.log(response);
                    console.log(cli);


                }
                catch (e) {

                }
            } else {
                console.log('invalid number');
                setMsg("Invalid Number");

            }

        }

    }

    function isPhoneNumberValid(phoneNumber){

            if (!/^\d+$/.test(phoneNumber)) {

                return false;
            }
            const ValidNumberRegex = /^(?:\+?88|0088)?01\d{9}$/;

            return ValidNumberRegex.test(phoneNumber);

    }
    return(
        <h1> {msg}</h1>
    )
}
