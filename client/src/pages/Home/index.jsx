import React, { useEffect, useState } from "react";
import useHomeLogic from "./useHomeLogic";

const Home = () => {
  const { 
    photos, 
    showUploadModal, 
    setShowUploadModal, 
    newPhoto, 
    setNewPhoto, 
    uploadPhoto 
  } = useHomeLogic();

  return (
    <div>
      <h1>Photo Gallery</h1>

      <button onClick={() => setShowUploadModal(true)} className="upload-button">
        Upload New Photo
      </button>

      <div className="photo-grid">
        {photos.length === 0 ? (
          <p>No photos available.</p>
        ) : (
          photos.map((photo) => (
            <div className="photo-item" key={photo.id}>
              <img src={photo.url} alt={photo.title} className="photo-image" />
              <p>{photo.title}</p>
              <p>{photo.description}</p>
              <small>Tags: {photo.tags}</small>
            </div>
          ))
        )}
      </div>

      {showUploadModal && (
        <div className="modal-overlay">
          <div className="modal-content">
            <button
              onClick={() => setShowUploadModal(false)}
              className="close-button"
            >
              &times;
            </button>
            <h2>Upload New Photo</h2>
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
            <input
              type="text"
              placeholder="Image URL"
              value={newPhoto.url}
              onChange={(e) => setNewPhoto({ ...newPhoto, url: e.target.value })}
            />
            <button onClick={uploadPhoto}>Upload</button>
          </div>
        </div>
      )}
    </div>
  );
};

export default Home;
