import React, { useEffect, useState } from "react";
import Button from "../../components/Button";
import { requestMethods } from "../../utils/enums/request.methods";
import { request } from "../../utils/remote/axios";
import { colors } from "../../utils/enums/colors.enum";
import { useNavigate } from "react-router-dom";

const Signup = () => {
  const [form, setForm] = useState({
    full_name:"",
    email: "",
    password: "",
  });
  const [errorMessage, setErrorMessage] = useState("");

  const navigate = useNavigate();


  const handleInputChange = (e) => {
    setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const signup = async () => {
    if (!form.full_name || !form.email || !form.password) {
      setErrorMessage("All fields are required.");
      return;
    }
  
    const response = await request({
      method: requestMethods.POST,
      route: "/signUp",
      body: form,
    });

    if (!response.error) {
      console.log(response);
      navigate("/auth");
    } else {
      setErrorMessage("Signup failed");
    }
  };

  return (
    <div className="flex column">
       <input
        type="text"
        name="full_name"
        placeholder="John Doe"
        onChange={handleInputChange}
      />
      <input
        type="text"
        name="email"
        placeholder="johnDoe@mail.com"
        onChange={handleInputChange}
      />
      <input
        type="password"
        name="password"
        placeholder="password"
        onChange={handleInputChange}
      />
      {errorMessage && <p style={{ color: "red" }}>{errorMessage}</p>}

      <Button text="Signup" onClick={signup} textColor={colors.BLACK} color="" />
      <p>Have an account? <a href="/auth">Login</a></p>

    </div>
  );
};

export default Signup;
