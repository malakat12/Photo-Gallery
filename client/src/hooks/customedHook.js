import React, { useEffect, useState } from "react";

const useCounter = (defaultValue) => {
  const [counter, setCounter] = useState(defaultValue);

  useEffect(() => {
    console.log(counter);
  }, [counter]);

  const increment = () => {
    setCounter(counter + 1);
  };

  const decrement = () => {
    setCounter(counter - 1);
  };

  const reset = () => {
    setCounter(0);
  };

  return [counter, increment, decrement, reset];
};

export default useCounter;
