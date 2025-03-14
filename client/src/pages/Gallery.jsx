import React, { useEffect, useState } from "react";
import { getPhotos, uploadPhoto, updatePhoto, deletePhoto } from "../services/api";
import PhotoCard from "../components/PhotoCard";
import PhotoForm from "../components/PhotoForm";
import Pagination from "../components/Pagination";
import SearchBar from "../components/SearchBar";
import "../styles/Gallery.css";

const Gallery = () => {
  const [photos, setPhotos] = useState([]); // All photos fetched from the API
  const [filteredPhotos, setFilteredPhotos] = useState([]); // Photos filtered by search
  const [editingPhoto, setEditingPhoto] = useState(null);
  const [loading, setLoading] = useState(true);
  const [showUploadModal, setShowUploadModal] = useState(false);
  const [searchTerm, setSearchTerm] = useState(""); // Search term state
  const [currentPage, setCurrentPage] = useState(1); // Current page for pagination
  const [photosPerPage] = useState(6); // Number of photos per page

  useEffect(() => {
    fetchPhotos();
  }, []);

  // Fetch photos from the API
  const fetchPhotos = async () => {
    const userId = localStorage.getItem("userId");
    if (!userId) {
      console.error("User ID not found");
      return;
    }

    try {
      const data = await getPhotos(userId);
      console.log("Fetched Data:", data);
      setPhotos(data.photos || []);
      setFilteredPhotos(data.photos || []); // Initialize filteredPhotos with all photos
    } catch (error) {
      console.error("Failed to fetch photos:", error);
    } finally {
      setLoading(false);
    }
  };

  // Handle search
  useEffect(() => {
    const filtered = photos.filter(
      (photo) =>
        photo.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
        photo.tags.toLowerCase().includes(searchTerm.toLowerCase())
    );
    setFilteredPhotos(filtered);
    setCurrentPage(1); // Reset to the first page when searching
  }, [searchTerm, photos]);

  // Handle photo deletion
  const handleDelete = async (id) => {
    await deletePhoto(id);
    fetchPhotos();
  };

  // Handle photo edit
  const handleEdit = (photo) => {
    setEditingPhoto(photo);
  };

  // Handle form submission (upload/update)
  const handleSubmit = async (formData) => {
    const userId = localStorage.getItem("userId");
    if (!userId) {
      console.error("User ID not found");
      return;
    }

    if (editingPhoto) {
      await updatePhoto(editingPhoto.id, { ...formData, user_id: userId });
    } else {
      await uploadPhoto({ ...formData, user_id: userId });
    }
    setEditingPhoto(null);
    setShowUploadModal(false);
    fetchPhotos();
  };

  // Pagination logic
  const indexOfLastPhoto = currentPage * photosPerPage;
  const indexOfFirstPhoto = indexOfLastPhoto - photosPerPage;
  const currentPhotos = filteredPhotos.slice(indexOfFirstPhoto, indexOfLastPhoto);

  // Total pages calculation
  const totalPages = Math.ceil(filteredPhotos.length / photosPerPage);
  console.log("Total Pages:", totalPages);
  // Change page
  const paginate = (pageNumber) => setCurrentPage(pageNumber);

  if (loading) {
    return <div>Loading...</div>;
  }

  return (
    <div className="gallery-container">
      <h1>Photo Gallery</h1>
      <button onClick={() => setShowUploadModal(true)} className="upload-button">
        Upload New Photo
      </button>

      {/* Search Bar */}
      <SearchBar searchTerm={searchTerm} setSearchTerm={setSearchTerm} />

      {/* Upload Modal */}
      {showUploadModal && (
        <div className="modal-overlay">
          <div className="modal-content">
            <button
              onClick={() => setShowUploadModal(false)}
              className="close-button"
            >
              &times;
            </button>
            <PhotoForm onSubmit={handleSubmit} />
          </div>
        </div>
      )}

      {/* Display Existing Photos */}
      <div className="photo-grid">
        {currentPhotos.map((photo) => (
          <PhotoCard
            key={photo.id}
            photo={photo}
            onEdit={handleEdit}
            onDelete={handleDelete}
          />
        ))}
      </div>

      {/* Pagination */}
      <Pagination
        currentPage={currentPage}
        totalPages={totalPages}
        paginate={paginate}
      />
    </div>
  );
};

export default Gallery;