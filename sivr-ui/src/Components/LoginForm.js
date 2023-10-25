import React, {useState} from "react";
import 'bootstrap/dist/css/bootstrap.css';
export default function LoginForm(){
    const [cli,setCli]=useState(0);

    function handleInputChange(e) {
        if(e.target.value.length<=11){
            setCli(e.target.value);
        }

    }

    function handleSubmit() {
console.log("phone number= +"+ cli);
    }

    return (
        <div id='loginForm' className='container-fluid p-5 d-flex flex-column gap-2'>
            <label>Enter your phone number :</label>
        <input type='number'  onChange={handleInputChange} value={cli} />
            <button className='btn btn-danger' onClick={handleSubmit}>Submit</button>
        </div>
    )
}
