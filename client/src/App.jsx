import React from "react";
import { BrowserRouter as Router, Route, Routes, Link } from "react-router-dom";
import Gallery from "./pages/Gallery";
import Upload from "./pages/Upload";

function App() {
    return (
        <Router>
            <nav>
                <Link to="/">Gallery</Link>
                <Link to="/upload">Upload</Link>
            </nav>
            <Routes>
                <Route path="/" element={<Gallery />} />
                <Route path="/upload" element={<Upload />} />
            </Routes>
        </Router>
    );
}

export default App;
