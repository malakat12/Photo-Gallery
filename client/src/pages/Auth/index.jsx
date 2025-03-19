import { Link } from "react-router-dom";
import React, { useEffect, useState } from "react";
import Button from "../../components/Button";
import { requestMethods } from "../../utils/enums/request.methods";
import { request } from "../../utils/remote/axios";
import { colors } from "../../utils/enums/colors.enum";
import { useNavigate } from "react-router-dom";
import "./style.css";


const Auth = () => {
  const [form, setForm] = useState({
    email: "",
    password: "",
  });
  const [errorMessage, setErrorMessage] = useState("");

  const navigate = useNavigate();


  const handleInputChange = (e) => {
    setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const login = async () => {
    if (!form.email || !form.password) {
      setErrorMessage("Email and password are required.");
      return;
    }
  
    const response = await request({
      method: requestMethods.POST,
      route: "/login",
      body: form,
      withCredentials: true, 
    });

    if (!response.error) {
      localStorage.setItem("user_id", response.user.id); 
      console.log(response);
      navigate("/home");
    } else {
      setErrorMessage(response.message || "Login failed. Please check your credentials.");
    }
  };

  return (
    <div className="auth-container">
      <div className="auth-box">
      <h2 className="auth-title">Login</h2>
      <input
        type="text"
        name="email"
        placeholder="email"
        onChange={handleInputChange}
      /><br></br>
      <input
        type="password"
        name="password"
        placeholder="password"
        onChange={handleInputChange}
      />
      {errorMessage && <p style={{ color: "red" }}>{errorMessage}</p>}

      <Button text="Login" onClick={login} className="auth-button"/>
      <p className="auth-link">Don't have an account? <Link to="/auth/signup">Sign Up</Link></p> 
      

    </div>
    </div>
  );
};

export default Auth;
