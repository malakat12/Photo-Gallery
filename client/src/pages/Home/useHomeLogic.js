import { request } from "../../utils/remote/axios";
import { requestMethods } from "../../utils/enums/request.methods";
import { useEffect, useState } from "react";
const useHomeLogic = () => {

  const [photos, setPhotos] = useState([]);
  const [showUploadModal, setShowUploadModal] = useState(false);
  const [newPhoto, setNewPhoto] = useState({ title: "", description: "", tags: "", url: "" });
  
  useEffect(() => {
    getPhotos();
  }, []);

  const getPhotos = async () => {
    const user_id = localStorage.getItem("user_id");
    if (!user_id) {
      console.error("User ID not found. User might not be logged in.");
      return;
    }
    const response = await request({
      method: requestMethods.GET,
      route: `/getAll?user_id=${user_id}`,
    });

    setPhotos(response.error ? [] : response);

  };

  const uploadPhoto = async () => {
    const user_id = localStorage.getItem("user_id");
    if (!user_id) {
      console.error("User ID not found. User might not be logged in.");
      return;
    }

    if (!newPhoto.title || !newPhoto.url) {
      alert("Title and Image URL are required!");
      return;
    }

    const response = await request({
      method: requestMethods.POST,
      route: `/upload`,
      body: { user_id, ...newPhoto },
    });

    if (!response.error) {
      setPhotos((prevPhotos) => [...prevPhotos, response]); 
      setShowUploadModal(false);
      setNewPhoto({ title: "", description: "", tags: "", url: "" });    }
  };
  
  return { photos,showUploadModal,setShowUploadModal, newPhoto,setNewPhoto,uploadPhoto  };
};

export default useHomeLogic;
