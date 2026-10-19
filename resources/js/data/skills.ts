// Static skill groups extracted VERBATIM from About.vue.
// Primary skill data still comes from the Laravel API (/portfolio/skills).
// This file is only a typed fallback / for chart labels.

export interface SkillGroup {
  title: string;
  items: string[];
  accent: 'copper' | 'electric';
}

export const skillGroups: SkillGroup[] = [
  {
    title: 'Frontend Development',
    items: ['Vue.js 3 & Composition API', 'React & Next.js', 'TypeScript', 'Tailwind CSS'],
    accent: 'copper',
  },
  {
    title: 'Backend Development',
    items: ['Laravel & PHP', 'RESTful API Design', 'MySQL & PostgreSQL', 'Authentication & Security'],
    accent: 'electric',
  },
  {
    title: 'Tools & DevOps',
    items: ['Git & GitHub', 'Docker & CI/CD', 'AWS & Cloud Services', 'Performance Optimization'],
    accent: 'copper',
  },
];
