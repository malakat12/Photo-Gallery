import React, { useEffect, useState } from "react";
import useHomeLogic from "./useHomeLogic";
import "./style.css";

const Home = () => {
  const { 
    photos, 
    showUploadModal, 
    setShowUploadModal, 
    newPhoto, 
    setNewPhoto, 
    handleFileChange, 
    uploadPhoto,
    deletePhoto, 
    startEditing, 
    updatePhoto,
    editingPhoto,
  } = useHomeLogic();
  const baseUrl = "http://localhost/Photo-Gallery/server/api";
  return (
    <div>
      <h1>Photo Gallery</h1>

      <button onClick={() =>{
        setShowUploadModal(true);
        setNewPhoto({ title: "", description: "", tags: "", photo: "" });
      } } className="upload-button">
        Upload New Photo
      </button>

      <div className="photo-grid">
        {photos.length === 0 ? (
          <p>No photos available.</p>
        ) : (
          photos.map((photo) => (
            <div className="photo-item" key={photo.id}>
              <img
                src={`${baseUrl}/${photo.url}`} 
                alt={photo.title}
                className="photo-image"
              />              
              <p>{photo.title}</p>
              <p>{photo.description}</p>
              <small>Tags: {photo.tags}</small>
              <div className="photo-actions">
                <button onClick={() => startEditing(photo)} className="edit-button">Edit</button>
                <button onClick={() => deletePhoto(photo.id)} className="delete-button">Delete</button>
              </div>
            </div>
          ))
        )}
      </div>

      {showUploadModal && (
        <div className="modal-overlay">
          <div className="modal-content">
            <button
              onClick={() => {
                setShowUploadModal(false);
                setNewPhoto({ title: "", description: "", tags: "", photo: "" }); // Reset newPhoto
              }}
              className="close-button"
            >
              &times;
            </button>
            <h2>{editingPhoto ? "Edit Photo" : "Upload New Photo"}</h2>
            <input
              type="text"
              placeholder="Title"
              value={newPhoto.title}
              onChange={(e) => setNewPhoto({ ...newPhoto, title: e.target.value })}
            />
            <input
              type="text"
              placeholder="Description"
              value={newPhoto.description}
              onChange={(e) => setNewPhoto({ ...newPhoto, description: e.target.value })}
            />
            <input
              type="text"
              placeholder="Tags (comma-separated)"
              value={newPhoto.tags}
              onChange={(e) => setNewPhoto({ ...newPhoto, tags: e.target.value })}
            />
            <input type="file" onChange={handleFileChange} />

            <button
              onClick={() => {
                console.log("Button clicked"); // Debugging log
                if (editingPhoto) {
                  console.log("Calling updatePhoto"); // Debugging log
                  updatePhoto();
                } else {
                  console.log("Calling uploadPhoto"); // Debugging log
                  uploadPhoto();
                }
              }}
            >
              {editingPhoto ? "Update" : "Upload"}
            </button>
          </div>
        </div>
      )}
    </div>
  );
};

export default Home;
