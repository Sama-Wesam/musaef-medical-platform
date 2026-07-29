import { createI18n } from 'vue-i18n';

const messages = {
  ar: {
    navbar: {
      home: 'الرئيسية',
      about: 'من نحن',
      features: 'مميزات المنصة',
      reviews: 'التقييمات والآراء',
      partners: 'بالتعاون مع',
      bloodGuide: 'ديل التبرع',
      medicalTips: 'الإرشادات الطبية',
      faq: 'الأسئلة الشائعة',
      login: 'تسجيل الدخول',
      register: 'إنشاء حساب',
      donateNow: 'تبرع الآن',
      announcement: 'تم إنقاذ 3,580 مريضاً بفضل المتبرعين.'
    },
    hero: {
      smallTitle: 'مسعف...',
      titleLine1: 'المنصة الذكية للتبرع',
      titleLine2: 'بالدم وقت الطوارئ',
      desc: 'نربط المتبرعين والمستشفيات بسرعة وذكاء لنضمن وصول الدم إلى من تحتاجه في الوقت المناسب.',
      explore: 'استكشف المنصة',
      donate: 'تبرع الآن'
    },
    about: {
      title: 'من نحن...',
      desc: 'منصة ذكية تهدف إلى تسهيل التبرع بالدم، إنقاذ الأرواح باستخدام الذكاء الاصطناعي والتقنيات الحديثة. نربط بين المتبرعين والمحتاجين في لحظات الطوارئ لضمان وصول الدم في الوقت المناسب.',
      visionTitle: 'رؤيتنا:',
      visionDesc: 'أن نكون المنصة الرائدة والأكثر موثوقية في مجال التبرع بالدم على مستوى الوطن العربي.',
      missionTitle: 'رسالتنا:',
      missionDesc: 'توفير الدم بأسرع وقت وبأعلى معايير الأمان من خلال منصة موثوقة تعتمد على التقنية والتعاون الإنساني لإنقاذ الأرواح.',
      goalsTitle: 'أهدافنا',
      goalsDesc: 'إنقاذ الأرواح عبر تسهيل التبرع بدم آمن وسريع وفعال باستخدام التقنية والذكاء الاصطناعي.',
      featuresTitle: 'مميزات المنصة؟!',
      reviewsTitle: 'التقييمات والآراء',
      partnersTitle: 'بالتعاون مع'
    },
    guide: {
      mainTitle: 'دليل التبرع',
      subTitle: 'والإرشادات الطبية',
      desc: 'دليل مختصر لفهم توافق فصائل الدم وشروط التبرع الآمن والإرشادات الطبية الأساسية لضمان تبرع آمن وإنقاذ الأرواح.',
      compTableTitle: 'جدول توافق فصائل الدم',
      donorToWhom: 'التوافق في التبرع (من يستطيع التبرع لمن؟)',
      donorToWhomDesc: 'يوضح من يمكنه التبرع لكل فصيلة دم بناءً على توافق فصائل الدم.',
      receiverFromWhom: 'التوافق في الاستقبال (من يستطيع استقبال الدم؟)',
      receiverFromWhomDesc: 'يوضح من يمكنه استقبال الدم من كل فصيلة دم بأمان.',
      compatible: 'متوافق',
      incompatible: 'غير متوافق',
      tipsTitle: 'قسم النصائح والإرشادات',
      ageTitle: 'العمر المناسب',
      ageDesc: 'يجب أن يكون عمرك بين 18 و 65 عاماً للتبرع بالدم.',
      weightTitle: 'الوزن المناسب',
      weightDesc: 'يجب أن يكون وزنك 50 كجم على الأقل للتبرع بالدم.',
      intervalTitle: 'متى يمكن التبرع مرة أخرى؟',
      intervalDesc: 'يمكنك التبرع كل 8 أسابيع (56 يوماً) للرجال، وكل 12 أسبوعاً (84 يوماً) للنساء.',
      faqTitle: 'الأسئلة الشائعة',
      contactSupport: 'إذا لم تجد الإجابة، تواصل معنا وسيقوم فريقنا بالرد عليك.'
    },
    footer: {
      desc: 'منصة ذكية لربط المتبرعين بالمحتاجين وتسهيل التبرع بالدم في وقت الطوارئ.',
      quickLinks: 'روابط سريعة',
      terms: 'شروط الاستخدام',
      termsDesc: 'الموافقة على سياسة الاستخدام',
      privacy: 'سياسة الخصوصية',
      privacyDesc: 'نحن نحترم بياناتك الشخصية',
      readMore: 'اقرأ المزيد',
      followUs: 'تابعنا',
      rights: 'جميع الحقوق محفوظة © منصة مسعف'
    }
  },
  en: {
    navbar: {
      home: 'Home',
      about: 'About Us',
      features: 'Features',
      reviews: 'Reviews',
      partners: 'In Collaboration With',
      bloodGuide: 'Donation Guide',
      medicalTips: 'Medical Tips',
      faq: 'FAQ',
      login: 'Login',
      register: 'Register',
      donateNow: 'Donate Now',
      announcement: '3,580 patients have been saved thanks to donors.'
    },
    hero: {
      smallTitle: 'Musaef...',
      titleLine1: 'Smart Platform for Blood',
      titleLine2: 'Donation in Emergency',
      desc: 'We connect donors and hospitals quickly and intelligently to ensure blood reaches those who need it in time.',
      explore: 'Explore Platform',
      donate: 'Donate Now'
    },
    about: {
      title: 'About Us...',
      desc: 'A smart platform aiming to facilitate blood donation and save lives using artificial intelligence and modern technology. We connect donors and recipients in emergency moments.',
      visionTitle: 'Our Vision:',
      visionDesc: 'To be the leading and most trusted blood donation platform across the Arab world.',
      missionTitle: 'Our Mission:',
      missionDesc: 'Providing blood in the fastest time with the highest safety standards through a reliable technology-driven platform.',
      goalsTitle: 'Our Goals',
      goalsDesc: 'Saving lives by enabling fast, safe, and effective blood donation utilizing AI.',
      featuresTitle: 'Platform Features?!',
      reviewsTitle: 'Reviews & Feedback',
      partnersTitle: 'In Collaboration With'
    },
    guide: {
      mainTitle: 'Donation Guide',
      subTitle: '& Medical Guidelines',
      desc: 'A concise guide to understanding blood group compatibility, safe donation conditions, and basic medical tips.',
      compTableTitle: 'Blood Group Compatibility Chart',
      donorToWhom: 'Donation Compatibility (Who can donate to whom?)',
      donorToWhomDesc: 'Shows who can donate to each blood group based on compatibility.',
      receiverFromWhom: 'Receiving Compatibility (Who can receive blood?)',
      receiverFromWhomDesc: 'Shows who can safely receive blood from each group.',
      compatible: 'Compatible',
      incompatible: 'Incompatible',
      tipsTitle: 'Tips & Guidelines Section',
      ageTitle: 'Eligible Age',
      ageDesc: 'You must be between 18 and 65 years old to donate blood.',
      weightTitle: 'Eligible Weight',
      weightDesc: 'Your weight must be at least 50 kg to donate blood.',
      intervalTitle: 'When can you donate again?',
      intervalDesc: 'Men can donate every 8 weeks (56 days), and women every 12 weeks (84 days).',
      faqTitle: 'Frequently Asked Questions',
      contactSupport: "If you didn't find the answer, contact us and our team will respond."
    },
    footer: {
      desc: 'Smart platform connecting blood donors with those in need during emergencies.',
      quickLinks: 'Quick Links',
      terms: 'Terms of Use',
      termsDesc: 'Agreement with usage policy',
      privacy: 'Privacy Policy',
      privacyDesc: 'We respect your personal data',
      readMore: 'Read More',
      followUs: 'Follow Us',
      rights: 'All rights reserved © Musaef Platform'
    }
  }
};

const savedLang = localStorage.getItem('musaef_lang') || 'ar';

export const i18n = createI18n({
  legacy: false,
  locale: savedLang,
  fallbackLocale: 'ar',
  messages
});
