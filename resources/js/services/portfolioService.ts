import api from './api';
import type { Project, Skill, Service, Experience, Education, Certificate, Testimonial, ApiResponse } from '@/types';

export const portfolioService = {
  async getPortfolio() {
    const { data } = await api.get<ApiResponse<Record<string, unknown>>>('/portfolio');
    return data;
  },
  async getProjects(params?: Record<string, unknown>) {
    const { data } = await api.get<ApiResponse<Project[]>>('/portfolio/projects', { params });
    return data;
  },
  async getProject(slug: string) {
    const { data } = await api.get<ApiResponse<{ project: Project; related: Project[] }>>(
      `/portfolio/projects/${slug}`,
    );
    return data;
  },
  async getSkills() {
    const { data } = await api.get<ApiResponse<Record<string, Skill[]>>>('/portfolio/skills');
    return data;
  },
  async getServices() {
    const { data } = await api.get<ApiResponse<Service[]>>('/portfolio/services');
    return data;
  },
  async getExperience() {
    const { data } = await api.get<ApiResponse<Experience[]>>('/portfolio/experience');
    return data;
  },
  async getEducation() {
    const { data } = await api.get<ApiResponse<Education[]>>('/portfolio/education');
    return data;
  },
  async getCertificates() {
    const { data } = await api.get<ApiResponse<Certificate[]>>('/portfolio/certificates');
    return data;
  },
  async getTestimonials() {
    const { data } = await api.get<ApiResponse<Testimonial[]>>('/portfolio/testimonials');
    return data;
  },
};
