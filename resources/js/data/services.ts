// Services extracted VERBATIM from Home.vue.

export interface ServiceItem {
  icon: string;
  title: string;
  description: string;
  features: string[];
}

export const services: ServiceItem[] = [
  {
    icon: '🚀',
    title: 'Web Development',
    description: 'Custom web applications built with cutting-edge technologies.',
    features: ['Responsive Design', 'RESTful APIs', 'Database Design', 'Performance'],
  },
  {
    icon: '💡',
    title: 'SaaS Solutions',
    description: 'Complete SaaS products from concept to deployment.',
    features: ['Multi-tenancy', 'Payments', 'Analytics', 'Scalability'],
  },
  {
    icon: '🎨',
    title: 'UI/UX Design',
    description: 'Beautiful interfaces with exceptional user experience.',
    features: ['Wireframing', 'Prototyping', 'User Testing', 'Design Systems'],
  },
  {
    icon: '⚡',
    title: 'API Development',
    description: 'Robust APIs with comprehensive documentation.',
    features: ['REST & GraphQL', 'Authentication', 'Rate Limiting', 'Versioning'],
  },
  {
    icon: '🔧',
    title: 'Consulting',
    description: 'Expert advice on architecture and best practices.',
    features: ['Code Review', 'Architecture', 'Performance', 'Security'],
  },
  {
    icon: '🛡️',
    title: 'Maintenance',
    description: 'Ongoing support to keep your apps running smoothly.',
    features: ['Bug Fixes', 'Updates', 'Monitoring', '24/7 Support'],
  },
];
