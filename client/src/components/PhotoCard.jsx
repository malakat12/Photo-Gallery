import React from "react";
import "../styles/PhotoCard.css";

const PhotoCard = ({ photo, onEdit, onDelete }) => {
  return (
    <div className="photo-card">
      <img src={photo.image} alt={photo.title} className="photo-image" />
      <h3>{photo.title}</h3>
      <p>{photo.description}</p>
      <div className="photo-actions">
        <button onClick={() => onEdit(photo)}>Edit</button>
        <button onClick={() => onDelete(photo.id)}>Delete</button>
      </div>
    </div>
  );
};

export default PhotoCard;