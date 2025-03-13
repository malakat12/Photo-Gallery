import React, { useEffect, useState } from "react";
import { getPhotos, deletePhoto } from "../services/api";

const Gallery = () => {
    const [photos, setPhotos] = useState([]);

    useEffect(() => {
        loadPhotos();
    }, []);

    const loadPhotos = async () => {
        try {
            const data = await getPhotos();
            setPhotos(data);
        } catch (error) {
            console.error("Error fetching photos:", error);
        }
    };

    const handleDelete = async (id) => {
        await deletePhoto(id);
        loadPhotos();
    };

    return (
        <div>
            <h2>Photo Gallery</h2>
            <div className="gallery">
                {photos.map((photo) => (
                    <div key={photo.id} className="photo-card">
                        <img src={`http://localhost/your-backend-folder/${photo.url}`} alt={photo.title} />
                        <h3>{photo.title}</h3>
                        <p>{photo.description}</p>
                        <button onClick={() => handleDelete(photo.id)}>Delete</button>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default Gallery;
