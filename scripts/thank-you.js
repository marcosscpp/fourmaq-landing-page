import triggerEvent from "./modules/trigger-event";
import {
  Check,
  CheckCircle,
  Clock,
  createIcons,
  Handshake,
  HeartHandshake,
  Mail,
  Map,
  MessageCircle,
  ShieldCheck,
  Smartphone,
  Star,
  Target,
  TrendingUp,
  UserCheck,
  Users,
  Zap,
} from "lucide";

triggerEvent("PageView Obrigado");

createIcons({
  icons: {
    Check,
    Clock,
    Handshake,
    HeartHandshake,
    Mail,
    Map,
    MessageCircle,
    ShieldCheck,
    Smartphone,
    Star,
    Target,
    TrendingUp,
    UserCheck,
    Users,
    Zap,
  },
});
