import React, { useState } from "react";
import "../styles/PhotoForm.css";

const PhotoForm = ({ initialData, onSubmit }) => {
  const [formData, setFormData] = useState(
    initialData || { title: "", description: "", tags: "", photo: null }
  );

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        setFormData({ ...formData, photo: reader.result }); // Base64 string
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = async (formData) => {
    const userId = localStorage.getItem("userId"); // Get user ID
    if (!userId) {
      console.error("User ID not found");
      return;
    }
  
    console.log("Form Data:", formData); // Debugging
  
    try {
      if (editingPhoto) {
        await updatePhoto(editingPhoto.id, { ...formData, user_id: userId });
      } else {
        await uploadPhoto({ ...formData, user_id: userId });
      }
      setEditingPhoto(null);
      setShowUploadModal(false); // Close the modal after upload
      fetchPhotos();
    } catch (error) {
      console.error("Failed to submit form:", error);
    }
  };
  return (
    <form onSubmit={handleSubmit} className="photo-form">
      <input
        type="text"
        name="title"
        placeholder="Title"
        value={formData.title}
        onChange={handleChange}
        required
      />
      <textarea
        name="description"
        placeholder="Description"
        value={formData.description}
        onChange={handleChange}
      />
      <input
        type="text"
        name="tags"
        placeholder="Tags"
        value={formData.tags}
        onChange={handleChange}
      />
      <input type="file" name="photo" onChange={handleFileChange} required />
      <button type="submit">{initialData ? "Update" : "Upload"}</button>
    </form>
  );
};

export default PhotoForm;