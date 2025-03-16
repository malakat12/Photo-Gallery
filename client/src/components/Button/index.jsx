import React from "react";

const Button = ({
  text,
  onClick,
  color = "black-bg",
  textColor = "white-text",
}) => {
  return (
    <button
      onClick={onClick}
      className={`button flex center ${color} ${textColor}`}
    >
      {text}
    </button>
  );
};

export default Button;
