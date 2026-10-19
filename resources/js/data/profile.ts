import type { ProfileData } from '@/types';

// NOTE: All text below is extracted VERBATIM from the existing views.
// Do NOT rewrite/shorten/translate. Only structural wrapping changed.

export const profileData: ProfileData = {
  profile: {
    name: 'Muhammad Ahmad',
    tagline: 'Full Stack Developer',
    description: 'Building modern web applications',
    email: 'sheikhahmad62720@gmail.com',
    phone: '+92 331 6918426',
    location: 'Haroonabad, Pakistan',
    social: {
      github: 'https://github.com/sheikhahmad62720-hash',
      linkedin:
        'https://www.linkedin.com/in/muhammad-ahmad-85135a28b?utm_source=share_via&utm_content=profile&utm_medium=member_android',
      facebook: undefined,
      instagram: 'https://www.instagram.com/ahmad_sheikh_79?igsh=dnVhM3I2enpxaXNh',
      twitter: 'https://x.com/ahmaddkhan628',
    },
  },
  roles: [
    'Full Stack Developer',
    'Laravel Expert',
    'Vue.js Specialist',
    'Backend Architect',
    'UI/UX Enthusiast',
  ],
  stats: [
    { value: '5+', label: 'Years Experience' },
    { value: '50+', label: 'Projects Delivered' },
    { value: '30+', label: 'Happy Clients' },
    { value: '100%', label: 'Satisfaction' },
  ],
  bio: [
    "I'm a passionate Full Stack Developer specializing in Laravel and Vue.js, currently working at NBTHUB. With over 5 years of experience, I've helped numerous clients bring their ideas to life through clean, scalable applications.",
    'My journey started with curiosity about how things work behind the scenes. Today, I combine technical expertise with creative problem-solving to build modern web applications that deliver exceptional performance and user experience.',
  ],
  info: [
    { label: 'Location', value: 'Haroonabad, Pakistan', icon: '📍' },
    { label: 'Company', value: 'NBTHUB', icon: '💼' },
    { label: 'Email', value: 'sheikhahmad62720@gmail.com', icon: '📧' },
    { label: 'Education', value: 'Computer Science', icon: '🎓' },
  ],
};

export const aboutHeader = {
  title: 'Full Stack Developer with a passion for excellence',
  subtitle: 'Building production-ready applications with modern technologies and best practices',
  sectionLabel: 'About Me',
};

export const heroCopy = {
  badge: 'Available for Freelance',
  headline: 'Hi, I’m',
  name: 'Muhammad Ahmad',
  description:
    'Crafting exceptional web experiences with Laravel & Vue.js. Currently building products at NBTHUB.',
  ctaPrimary: 'View My Work →',
  ctaSecondary: 'Get In Touch',
};
