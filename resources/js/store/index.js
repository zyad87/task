// store.js
import { createStore } from 'vuex';
import axios from 'axios';

const store = createStore({
    state: {
        tasks: []
    },
    mutations: {
        // تعيين المهام في حالة Vuex
        setTasks(state, tasks) {
            state.tasks = tasks;
        },
        // إضافة مهمة جديدة إلى حالة Vuex
        addTask(state, task) {
            state.tasks.push(task);
        },
        // حذف المهمة من حالة Vuex
        removeTask(state, taskId) {
            state.tasks = state.tasks.filter(task => task.id !== taskId);
        },
        // تحديث المهمة في حالة Vuex
        updateTask(state, updatedTask) {
            const index = state.tasks.findIndex(task => task.id === updatedTask.id);
            if (index !== -1) {
                state.tasks.splice(index, 1, updatedTask);
            }
        }
    },
    actions: {
        // تحميل المهام من API
        async loadTasks({ commit }) {
            try {
                const response = await axios.get('/api/tasks');
                commit('setTasks', response.data);
            } catch (error) {
                console.error("Failed to load tasks:", error.response ? error.response.data : error.message);
            }
        },
        // إنشاء مهمة جديدة وإضافتها إلى حالة Vuex
        async createTask({ commit }, taskData) {
            try {
                const response = await axios.post('/api/tasks', taskData);
                commit('addTask', response.data);
            } catch (error) {
                console.error("Failed to create task:", error.response ? error.response.data : error.message);
            }
        },
        // حذف المهمة
        async deleteTask({ commit }, taskId) {
            try {
                await axios.delete(`/api/tasks/${taskId}`);
                commit('removeTask', taskId);
            } catch (error) {
                console.error("Failed to delete task:", error.response ? error.response.data : error.message);
            }
        },
        // تحديث المهمة
        async updateTask({ commit }, taskData) {
            try {
                const response = await axios.put(`/api/tasks/${taskData.id}`, taskData);
                commit('updateTask', response.data);
            } catch (error) {
                console.error("Failed to update task:", error.response ? error.response.data : error.message);
            }
        }
    }
});

export default store;
