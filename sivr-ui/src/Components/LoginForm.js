import React, {useState} from "react";
import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.css';
export default function LoginForm(){
    const [formData,setFormData]=useState(
        {cli:0}
    );
    const [url,setUrl]=useState('http://localhost:3000/sivr/auth?');
    const handleInputChange = (e) => {
        const {name, value} = e.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };
    function isPhoneNumberValid(phoneNumber){

        if (!/^\d+$/.test(phoneNumber)) {

            return false;
        }
        const ValidNumberRegex = /^(?:\+?88|0088)?01\d{9}$/;

        return ValidNumberRegex.test(phoneNumber);

    }

    async function handleSubmit() {
if (isPhoneNumberValid(formData.cli)){
            const response = await axios.post('http://127.0.0.1:8000/api/generate-auth-link',formData);
            setUrl('http://localhost:3000/sivr/auth?'+response.data )

            console.log(response.data);
            console.log(formData.cli);
        }
else{
    setFormData({
        cli: 0
    })
    setUrl('http://localhost:3000/sivr/auth?');
}


    }

    return (
        <div id='loginForm' className='container-fluid p-5 d-flex flex-column gap-2'>
            <label>Enter your phone number :</label>
        <input type='number' name='cli'  onChange={handleInputChange} value={formData.cli} />
            <button className='btn btn-danger' onClick={handleSubmit}>Submit</button>
            <div>
                <p>Output Data: </p>
                <a target="_blank" href={url}>{url} </a>

            </div>

        </div>
    )
}
