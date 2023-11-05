import React from "react";
import './App.css';
import './css/style.css'
import LoginForm from "./Components/LoginForm";
import {BrowserRouter,Route,Routes} from "react-router-dom";
import LoginWithApiUrl from "./Components/LoginWithApiUrl";
import HomePage from "./Components/HomePage";



function App() {
  return (
      <BrowserRouter><Routes>
          <Route path='/' element={<LoginForm/>}></Route>
    <Route path='/sivr/auth' element={<LoginWithApiUrl/>}></Route>
          <Route path={'/homePage'} element={<HomePage/>}></Route>
      </Routes></BrowserRouter>
  )
    ;
}

export default App;
