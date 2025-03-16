import { BrowserRouter, Routes, Route, useLocation } from "react-router-dom";
import "./styles/App.css";
import Auth from "./pages/Auth";
import Home from "./pages/Home";
import SideBar from "./components/SideBar";
import Signup from "./pages/Auth/signUp";

const App = () => {
  const { pathname } = useLocation();

  return (
    <>
      {/* <nav>Navbar</nav> */}
      <div className="flex">
        {/* Conditional rendering */}
        <Routes>
          <Route path="/auth" element={<Auth />} />
          <Route path="/auth/signup" element={<Signup />} />
          <Route path="/" element={<Home />} />
        </Routes>
      </div>
    </>
  );
};

export default App;
