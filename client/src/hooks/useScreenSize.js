import React, { useEffect, useState } from "react";

const useScreenSize = () => {
  const [height, setHeight] = useState(0);
  const [width, setWidth] = useState(0);
  const [size, setSize] = useState("");

  useEffect(() => {
    window.addEventListener("resize", (e) => {
      const h = e.target.screen.height;
      const w = e.target.screen.width;

      setHeight(h);
      setWidth(w);
    });
  }, []);

  useEffect(() => {
    if (width < 450) {
      setSize("mobile");
    } else if (width < 840) {
      setSize("tablet");
    } else {
      setSize("pc");
    }
  }, [width]);

  return { width, height, size };
};

export default useScreenSize;
