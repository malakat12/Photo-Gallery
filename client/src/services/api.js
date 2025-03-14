import axios from "axios";

const API_BASE_URL = "http://localhost/Photo-Gallery/server/api/v1"; // Replace with your backend URL

const api = axios.create({
  baseURL: API_BASE_URL,
});

export const loginUser = async (userData) => {
  try {
    const response = await api.post("/login", userData);
    return response.data;
  } catch (error) {
    console.error("Login failed:", error);
    return null;
  }
};

export const signupUser = async (userData) => {
  try {
    const response = await api.post("/signUp", userData);
    return response.data;
  } catch (error) {
    console.error("Signup failed:", error);
    return null;
  }
};

export const getPhotos = async (userId) => {
  try {
    const response = await api.get("/getAll", {
      params: { user_id: userId },
    });
    console.log("API Response:", response.data); 
    return response.data; 
  } catch (error) {
    console.error("Failed to fetch photos:", error);
    return []; 
  }
};

export const uploadPhoto = async (photoData) => {
  try {
    const response = await api.post("/upload", photoData, {
      headers: {
        "Content-Type": "multipart/form-data", 
      },
    });
    return response.data;
  } catch (error) {
    console.error("Failed to upload photo:", error);
    return null;
  }
};

export const updatePhoto = async (id, updatedData) => {
  try {
    const response = await api.put(`/update/${id}`, updatedData);
    return response.data;
  } catch (error) {
    console.error("Failed to update photo:", error);
    return null;
  }
};

export const deletePhoto = async (id) => {
  try {
    const response = await api.delete(`/delete/${id}`);
    return response.data;
  } catch (error) {
    console.error("Failed to delete photo:", error);
    return null;
  }
};