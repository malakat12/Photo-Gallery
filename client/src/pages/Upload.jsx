import React, { useState } from "react";
import { uploadPhoto } from "../services/api";

const Upload = () => {
    const [photo, setPhoto] = useState(null);
    const [title, setTitle] = useState("");
    const [description, setDescription] = useState("");
    const [tags, setTags] = useState("");

    const handleSubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append("photo", photo);
        formData.append("title", title);
        formData.append("description", description);
        formData.append("tags", tags);

        try {
            await uploadPhoto(formData);
            alert("Photo uploaded successfully!");
        } catch (error) {
            console.error("Upload failed:", error);
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            <input type="text" placeholder="Title" value={title} onChange={(e) => setTitle(e.target.value)} required />
            <input type="text" placeholder="Description" value={description} onChange={(e) => setDescription(e.target.value)} required />
            <input type="text" placeholder="Tags (comma-separated)" value={tags} onChange={(e) => setTags(e.target.value)} required />
            <input type="file" accept="image/*" onChange={(e) => setPhoto(e.target.files[0])} required />
            <button type="submit">Upload</button>
        </form>
    );
};

export default Upload;
