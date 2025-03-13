import axios from "axios";

const API_BASE_URL = "http://localhost/your-backend-folder"; // Adjust URL

const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        "Content-Type": "application/json",
    },
});

export const getPhotos = async () => {
    const response = await api.get("/photos");
    return response.data;
};

export const uploadPhoto = async (formData) => {
    const response = await api.post("/upload-photo", formData, {
        headers: { "Content-Type": "multipart/form-data" },
    });
    return response.data;
};

export const updatePhoto = async (id, data) => {
    const response = await api.post(`/update-photo`, data);
    return response.data;
};

export const deletePhoto = async (id) => {
    const response = await api.post(`/delete-photo`, { id });
    return response.data;
};

export default api;
