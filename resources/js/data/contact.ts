// Contact details extracted VERBATIM from PublicLayout.vue footer + Contact.vue.

export interface ContactInfo {
  email: string;
  phone: string;
  phoneHref: string;
  location: string;
  social: {
    label: string;
    href: string;
  }[];
}

export const contactInfo: ContactInfo = {
  email: 'sheikhahmad62720@gmail.com',
  phone: '+92 331 6918426',
  phoneHref: 'tel:+923316918426',
  location: 'Haroonabad, Pakistan',
  social: [
    { label: 'GitHub', href: 'https://github.com/sheikhahmad62720-hash' },
    {
      label: 'LinkedIn',
      href: 'https://www.linkedin.com/in/muhammad-ahmad-85135a28b?utm_source=share_via&utm_content=profile&utm_medium=member_android',
    },
    { label: 'Twitter', href: 'https://x.com/ahmaddkhan628' },
    { label: 'Instagram', href: 'https://www.instagram.com/ahmad_sheikh_79?igsh=dnVhM3I2enpxaXNh' },
  ],
};

export const contactFormStrings = {
  title: 'Send Me a Message',
  nameLabel: 'Full Name *',
  namePlaceholder: 'John Doe',
  emailLabel: 'Email Address *',
  emailPlaceholder: 'john@example.com',
  phoneLabel: 'Phone Number',
  phonePlaceholder: '+92 331 6918426',
  subjectLabel: 'Subject',
  subjectPlaceholder: 'Project Inquiry',
  messageLabel: 'Message *',
  messagePlaceholder: 'Tell me about your project...',
  submitLabel: 'Send Message',
  successMessage: "Thank you! Your message has been sent successfully. I'll get back to you soon.",
  availabilityTitle: 'Available for Freelance',
  availabilityText:
    "I'm currently available for freelance projects and consulting opportunities. Let's build something amazing together!",
};
