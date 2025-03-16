import { Link } from "react-router-dom";
import React, { useEffect, useState } from "react";
import Button from "../../components/Button";
import { requestMethods } from "../../utils/enums/request.methods";
import { request } from "../../utils/remote/axios";
import { colors } from "../../utils/enums/colors.enum";
import { useNavigate } from "react-router-dom";

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
      navigate("/");
    } else {
      setErrorMessage(response.message || "Login failed. Please check your credentials.");
    }
  };

  return (
    <div className="flex column">
      <input
        type="text"
        name="email"
        placeholder="email"
        onChange={handleInputChange}
      />
      <input
        type="password"
        name="password"
        placeholder="password"
        onChange={handleInputChange}
      />
      {errorMessage && <p style={{ color: "red" }}>{errorMessage}</p>}

      <Button text="Login" onClick={login} textColor={colors.BLACK} color="" />
      <p>Don't have an account?</p> 
      <Link to="/auth/signup">Sign Up</Link>

    </div>
  );
};

export default Auth;
