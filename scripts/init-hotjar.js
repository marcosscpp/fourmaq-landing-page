import Hotjar from "@hotjar/browser";

export default function initHotjar() {
  const siteId = 6460975;
  const hotjarVersion = 6;

  Hotjar.init(siteId, hotjarVersion);
}
