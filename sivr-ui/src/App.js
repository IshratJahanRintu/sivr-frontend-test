import React from "react";
import './App.css';
import './css/style.css'
import LoginForm from "./Components/LoginForm";
import {BrowserRouter,Route,Routes} from "react-router-dom";
import LoginWithApiUrl from "./Components/LoginWithApiUrl";



function App() {
  return (
      <BrowserRouter><Routes>
          <Route path='/' element={<LoginForm/>}></Route>
    <Route path='/sivr/auth' element={<LoginWithApiUrl/>}></Route>
      </Routes></BrowserRouter>
  )
    ;
}

export default App;
