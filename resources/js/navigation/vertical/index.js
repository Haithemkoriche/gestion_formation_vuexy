export default [
  {
    title: "Home",
    to: { name: "root" },
    icon: { icon: "tabler-smart-home" },
  },
  // {
  //   title: 'Second page',
  //   to: { name: 'second-page' },
  //   icon: { icon: 'tabler-file' },
  // },
  {
    title: "Gestion des services",
    to: { name: "services-services" },
    icon: { icon: "tabler-settings" },
  },
  {
    title: "Gestion des status",
    to: { name: "services-status" },
    icon: { icon: "tabler-settings-check" },
  },
  {
    title: "Gestion de caisse",
    children: [
      {
        title: "Ciasse",
        to: { name: "caisses-caise" },
        icon: { icon: "tabler-settings-check" },
      },
      {
        title: "ajouter transaction",
        to: { name: "caisses-caissetransactions" },
        icon: { icon: "tabler-settings-check" },
      },
    ],
    icon: { icon: "tabler-settings-check" },
  },
  // {
  //   title: "Gestion des admission",
  //   to: {name: 'services-services'},
  //   icon: {icon: 'tabler-school'},
  // },
  // {
  //   title: "Gestion des visas",
  //   to: {name: 'services-services'},
  //   icon: {icon: 'tabler-brand-visa'},
  // },
];
