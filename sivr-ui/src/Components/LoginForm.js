import React, {useState} from "react";
import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.css';
export default function LoginForm(){
    const [formData,setFormData]=useState(
        {cli:0}
    );
    const [url,setUrl]=useState('http://localhost:3000/sivr/auth?');
const [longCode,setLongCode]=useState(0)
    const handleInputChange = (e) => {
        const {name, value} = e.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };


    async function handleSubmit() {
        console.log("phone number= +" + formData.cli);

        const response = await axios.post('http://127.0.0.1:8000/api/generate-auth-link',formData);
       setUrl(prevUrl =>prevUrl+response.data+formData.cli )

        console.log(response.data);
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
